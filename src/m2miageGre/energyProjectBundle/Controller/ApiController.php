<?php

namespace m2miageGre\energyProjectBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\SerializerInterface;
use m2miageGre\energyProjectBundle\Model\Capteur as propelCapteur;
use m2miageGre\energyProjectBundle\Model\MesureQuery;
use m2miageGre\energyProjectBundle\Model\serializeModel\Household;
use m2miageGre\energyProjectBundle\Model\serializeModel\Mesure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use m2miageGre\energyProjectBundle\Model\CapteurQuery;
use m2miageGre\energyProjectBundle\Model\serializeModel\Capteur;

/**
 * @DI\Service("m2miagegre.api.controller")
 */
class ApiController extends Controller
{
    public function apiAction(HttpRequest $request)
    {
        $version = $request->get("version");
        $year = $request->get("year");
        $dayThreshold = $request->get("threshold");
        $household = $request->get("household");

        $capteur = CapteurQuery::create()
            ->filterByHouseholdId($household)
            ->filterByVersion($version)
            ->find();

        $searchDate = "1998-01-24";
        $mesures = MesureQuery::create()
            ->filterByCapteur($capteur)
            ->filterByTimestamp(array("min" => $searchDate." 00:00:00", "max" => $searchDate." 23:59:59"))
            ->find();


        return new Response($mesures->toJSON(false, true));
    }

    public function apiActionV2(HttpRequest $request)
    {
        $version = $request->get("version");
        $householdId = $request->get("household");
        $year = $request->get("year");
        $month = $request->get("month");
        $day = $request->get("day");

        $date = date_create_from_format("Y-m-d H:i:s", "$year-$month-$day 00:00:00", new \DateTimeZone("Europe/Paris"));

        $household = new Household($date, $householdId);

        $capteurs = CapteurQuery::create()
            ->filterByHouseholdId($householdId)
            ->filterByVersion($version)
            ->find();

        $searchDate = date_create_from_format("Y-m-d H:i:s", "$year-$month-$day 00:00:00", new \DateTimeZone("Europe/Paris"));

        $prevSearchDate = clone($searchDate);
        $prevSearchDate->modify("-1 day");

        /** @var $capteur propelCapteur */
        foreach ($capteurs as $capteur) {
            $prevMesureSearchDate = $prevSearchDate->format("Y-m-d");
            $prevMesures = MesureQuery::create()
                ->filterByCapteur($capteur)
                ->filterByTimestamp(array("max" => "$prevMesureSearchDate 23:59:59"))
                ->orderByTimestamp(\Criteria::DESC)
                ->limit(1)
                ->find();

            $prevMesure = $prevMesures->getFirst();
            /** @var \m2miageGre\energyProjectBundle\Model\Mesure $prevMesure */
            $prevMesure = new Mesure($prevMesure->getEnergy(), $prevMesure->getState(), $prevMesure->getTimestamp());

            $household->addCapteur(new Capteur($capteur->getCapteurName(), $capteur->getId(), $capteur->getVersion(), $prevMesure));
        }

        $mesureSearchDate = $searchDate->format("Y-m-d");
        $mesures = MesureQuery::create()
            ->filterByCapteur($capteurs)
            ->filterByTimestamp(array("min" => $mesureSearchDate." 00:00:00", "max" => $mesureSearchDate." 23:59:59"))
            ->orderByTimestamp()
            ->find();

        foreach ($mesures as $propMesure) {
            $household->addMesure($propMesure);
        }
        $household->fillGap();

        return new Response($this->serializer->serialize($household, "json"));
    }

    public function computeFourDates($thresold)
    {

    }

    /**
     * @var \JMS\Serializer\Serializer $serializer
     * @DI\Inject("serializer")
     */
    public $serializer;
}
