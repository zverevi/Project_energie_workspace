package vue;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.LinkedList;
import java.util.List;

import modele.inputmanagment.Dossier;

public class MainVue {
	

	public static final int MAIN_MENU = 0;
	public static final int FILE_MENU = 1;

	public static final int EXIT_CODE = 0;
	public static final int LIRE_FICHIER_CODE = 1;
	public static final int LIRE_TOUT_FICHIER_CODE = 111;
	
	public static final int GODMODE_CODE = 3615;

	private static MainVue vue;
	Connection base = null;
	
	public static synchronized MainVue getInstance()
    {
            if(vue==null)
            {
                   vue = new MainVue();
            }
            return vue;
    }

	public void mainMenu() {
		System.out.println("___________________________ PROJET BD ___________________________");
		System.out.println("");
		System.out.println("1 : Consulter la liste des fichiers dans le dossier");
		System.out.println("0 : Quitter");
		System.out.println("_________________________________________________________________");
		System.out.print("Votre choix : ");
	}
	public void fileMenu(Dossier D) {
		System.out.println("___________________________ PROJET BD ___________________________");
		System.out.println("(aff que 10 fichiers max)");
		int i =0;
		String [] liste = D.listeFichiers();
		while(i<liste.length && i<10){
			System.out.println((i+1)+" : Choisir le fichier ["+liste[i]+"]");
			i++;
		}
		System.out.println("111 : Lire tous les fichiers");
		System.out.println("0 : Quitter");
		System.out.println("_________________________________________________________________");
		System.out.print("Votre choix : ");
	}

	public List afficherListeAlbum(ResultSet listeAlbum) {
		List album = new LinkedList<Integer>();
		
		try {
			while(listeAlbum.next()) {
				System.out.println(listeAlbum.getInt("IDALBUM") + " : " + listeAlbum.getString("TITRE"));
				album.add(listeAlbum.getInt("IDALBUM"));
			}
			System.out.print("Votre choix : ");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return album;
		
	}
	
	public void afficherListePhoto(ResultSet listePhoto) {
		try {
			while(listePhoto.next()) {
				System.out.println(listePhoto.getInt("IDALBUM") + "-"+listePhoto.getInt("NUMPHOTO")+" : " + listePhoto.getString("TITREP"));
			}
			System.out.print("Numéro de la photo à modifier (ex : 1-34) : ");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}


	public List afficherListeFichier(ResultSet listeFichier) {
		List fichier = new LinkedList<Integer>();
		try {
			while(listeFichier.next()) {
				System.out.println(listeFichier.getInt("FID") + " : " + listeFichier.getString("CHEMIN"));
				fichier.add(listeFichier.getInt("FID"));
			}
			System.out.print("Votre choix : ");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return fichier;
		
	}

	public void demanderIdFichier() {
		System.out.print("Entrez l'identifiant du fichier : ");
	}

	public void demanderTitreFichier() {
		System.out.print("Entrez le titre du fichier : ");
		
	}

	public void demanderCommentaireFichier() {
		System.out.print("Entrez un commaentaire pour le fichier : ");
	}

	public void demanderContinuer() {
		System.out.print("Voulez vous continuer ? (o/n) : ");
		
	}

	public void menuAdmin() {
		System.out.println("************************ MODE MAITRE ************************ ");
		System.out.println("");
		System.out.println("3 : Incrémenter stock");
		System.out.println("2 : Supprimer un fichier");
		System.out.println("1 : Sortir du mode admin");
		System.out.println("0 : Quitter");
		System.out.println("************************************************************* ");
		System.out.print("Votre choix : ");
		
	}

}
