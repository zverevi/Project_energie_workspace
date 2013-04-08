package modele.inputmanagment;

import java.io.File;

public class Dossier {
	public static File dossier;
	public static String cheminComplet;
	
	public Dossier(String rep){
		dossier = new File (rep);
		cheminComplet=rep;
	}
	public String [] listeFichiers(){
		return dossier.list();
	}
	public String fichier(int num){
		String [] listefichiers = dossier.list();
		int i = 0;
		while(i!=num && i<listefichiers.length){ 
			i++;
		}
		return listefichiers[i];
	}
	public String cheminComplet(){
		return cheminComplet;
	}

}
