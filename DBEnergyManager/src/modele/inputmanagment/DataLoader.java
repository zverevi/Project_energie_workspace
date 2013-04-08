package modele.inputmanagment;

import java.io.*;
import java.util.StringTokenizer;

import javax.activation.DataSource;

import modele.BdConn.DataStorage;

public class DataLoader implements DataLoaderInterface{
	
	public int afficherTousFichiersDossier(Dossier D){
		String [] listefichiers;
		int i; 
		
		System.out.println("");
		listefichiers=D.listeFichiers(); 
		for(i=0;i<listefichiers.length;i++){ 
			if(listefichiers[i].endsWith(".txt")==true)
				System.out.println("* "+listefichiers[i]);		
		}
		System.out.println("");
		
		return listefichiers.length;
	}
	
public void lireToutFichier(Dossier D) {
		String [] fichiers = D.listeFichiers();
		for (int num=1;num<=fichiers.length;num++){
			lireFichier(num, D);
		}
	}
	
	public void lireFichier(int num, Dossier D) {
		
		String fichier = D.cheminComplet()+D.fichier(num-1);
		System.out.println("fichier ["+D.fichier(num-1)+"] a été choisi");
		try
		{
			File f = new File (fichier);
		    FileReader fr = new FileReader (f);
		    BufferedReader br = new BufferedReader (fr);
		 
		    try
		    {
		        String line = br.readLine();

				DataStorage outilOracle = new DataStorage();
		        while (line != null)
		        {
		        	detecterProjet(line, outilOracle);
		        	detecterEquipement(line, br, outilOracle); //avancement line
		        	detecterReleve(line, br, outilOracle); //avancement line
		            line = br.readLine();
		        }
		 
		        br.close();
		        fr.close();
		    }
		    catch (IOException exception)
		    {
		        System.out.println ("Erreur lors de la lecture : " + exception.getMessage());
		    }
		}
		catch (FileNotFoundException exception)
		{
		    System.out.println ("Le fichier n'a pas été trouvé");
		}
		
	}

	private void detecterProjet(String line, DataStorage outilOracle) {
		String nomProjet = null;
		String lieuProjet = null;
		
		if(line.contains("PROJECT")){
			System.out.println("Début calculs sur le Projet");
			line = line.substring(10);
			String [] sp = line.split("\\(");
			if ( sp != null && sp.length == 2 ){
				String [] nomTemp = sp[0].split("\\ ");
				nomProjet = nomTemp[0];
				String [] lieuTemp = sp[1].split("\\)");
				lieuProjet = lieuTemp[0];
		    }else{
		    	System.out.println("Le problème est arrivé lors du split du nom/lieu du projet");
		    }
			if(nomProjet != null && lieuProjet!=null){
				System.out.println("---insert Projet");
				outilOracle.insertProjet(nomProjet, lieuProjet);
				System.out.println("---OK");
			}
		}
	}

	private void detecterEquipement(String line, BufferedReader br, DataStorage outilOracle) {
		String nomEquipement = null;
		String maisonEquipement = null;
		
		if(line.contains("HOUSEHOLD")){
			System.out.println("Début calculs sur l'Equipement");
			maisonEquipement = line.substring(12);
			try {
				line = br.readLine();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			while(line != null && !line.contains("APPLIANCE")){
				//attention !
				try {
					line = br.readLine();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			if(line.contains("APPLIANCE")){
				System.out.println(line);
				nomEquipement = line.substring(12);
			}
			
			if(maisonEquipement != null && nomEquipement!=null){
				System.out.println("---insert Maison");
				outilOracle.insertMaison(maisonEquipement);
				System.out.println("---OK");
				
				System.out.println("---insert Equipement");
				outilOracle.insertEquipement(nomEquipement, maisonEquipement);
				System.out.println("---OK");
			}
		}
	}

	private void detecterReleve(String line, BufferedReader br, DataStorage outilOracle) {
		
		int nbLine = 0;
		int nbInsert = 0;
		String date_Releve = null;
		String heure_Releve = null;
		String etat_Releve = null;
		String conso_Releve = null;
		if(line.contains("DATE(dd/mm/yy)	TIME(hh:mm)	STATE	ENERGY(Wh)")){
			System.out.println("Exécution des insertions dans la BD. Patientez ...");
			try {
				line = br.readLine();
				while(line!= null){
					StringTokenizer st = new StringTokenizer(line);
					if(st.countTokens()==4){
						date_Releve 	= (String) st.nextElement();
						heure_Releve 	= (String) st.nextElement();
						etat_Releve 	= (String) st.nextElement();
						conso_Releve 	= (String) st.nextElement();
						outilOracle.insertReleve(date_Releve, heure_Releve, etat_Releve, conso_Releve);
						nbInsert++;
					}
					nbLine++;
					line = br.readLine();
				}
				System.out.println(nbLine+" d'éléments trouvés");
				System.out.println(nbInsert+" d'éléments insérés dans la BD");
			} catch (IOException e1) {
				// TODO Auto-generated catch block
				e1.printStackTrace();
			}
		}
	}
	
//	public static void main(String [] args){
//		String line = "22/01/98	13:40	0	000294";
//		
//			StringTokenizer st = new StringTokenizer(line);
//
//			System.out.println(st.nextElement());
//			System.out.println(st.nextElement());
//			System.out.println(st.nextElement());
//			System.out.println(st.nextElement());
//	}

}
