<?php

namespace m2miageGre\energyProjectBundle\Controller;

use JMS\DiExtraBundle\Annotation as DI;
use m2miageGre\energyProjectBundle\Model\MesureQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response;
use m2miageGre\energyProjectBundle\Model\CapteurQuery;

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
//            ->filterByVersion($version)
            ->find();

        $searchDate = "1998-01-24";
        $mesures = MesureQuery::create()
            ->filterByCapteur($capteur)
            ->filterByTimestamp(array("min" => $searchDate." 00:00:00", "max" => $searchDate." 23:59:59"))
            ->find();


        return new Response($mesures->toJSON(false, true));
    }

    public function computeFourDates($thresold)
    {

    }
}
