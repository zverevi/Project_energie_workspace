package controleur.launcher;

import vue.MainVue;
import lib.LectureClavier;
import modele.inputmanagment.DataLoader;
import modele.inputmanagment.Dossier;

public class Launcher {
	public static void main (String[] args){
		
		/* Début : Paramètres de l'application */
			//Initialisation de la lecture clavier
			LectureClavier lecteur = new LectureClavier();
			//DataLoader
			DataLoader DL = new DataLoader();
			//Numéro représentant l'action choisie (selon les numéros affichés par MainVue)
			int action; 
			//True si on veux quitter l'application
			boolean exit = false;
			String dossierPrincipal = "C:/Users/ZIK/Documents/M2 MIAGE/M2 MIAGE cours/2 Projet Energie S2/data/br/";
			Dossier D = new Dossier(dossierPrincipal);
			int nbEltsDossierPrincipal = 0;
		/* Fin : Paramètres de l'application */
			
		int menu=MainVue.MAIN_MENU;
		while (!exit) {
		/* Début : Mode administrateur */
			if(menu==MainVue.MAIN_MENU){
				MainVue.getInstance().mainMenu(); // Affichage du menu principal de l'application
				action = lecteur.lireEntier("");
				switch (action) {
				case MainVue.EXIT_CODE:
					exit = true;
					break;
				case MainVue.LIRE_FICHIER_CODE:
					DataLoader dl = new DataLoader();
					nbEltsDossierPrincipal = dl.afficherTousFichiersDossier(D);
					menu = MainVue.FILE_MENU;
					break;
				default:
					System.out.println("=> choix incorrect");
				}
			}else if (menu==MainVue.FILE_MENU){
				MainVue.getInstance().fileMenu(D); // Affichage du menu principal de l'application
				action = lecteur.lireEntier("");
				switch (action) {
				case MainVue.EXIT_CODE:
					exit = true;
					break;
				case MainVue.LIRE_TOUT_FICHIER_CODE:
					DataLoader dl = new DataLoader();
					dl.lireToutFichier(D);
					break;
				default:
					if(action>10 || action<0){
						System.out.println("=> choix incorrect");
					}else{
						DataLoader dl2 = new DataLoader();
						dl2.lireFichier(action,D);
					}
				}
				menu = MainVue.MAIN_MENU;
			}
			/* Fin : Mode utilisateur enregistré et loggué */
		}
		//récupérer des fichiers
		
		//boucler sur l'ensemble des fichiers
		//DL.lireFichier("C:/Users/ZIK/Documents/M2 MIAGE/M2 MIAGE cours/2 Projet Energie S2/data/br/1000080-2000903-3009935.txt");
		
	}
}
