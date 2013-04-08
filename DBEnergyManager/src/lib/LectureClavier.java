package lib;

import java.io.*;
import java.util.LinkedList;
import java.util.List;
import java.util.NoSuchElementException;
import java.util.StringTokenizer;

/**
 *
 *   Cette classe a pour role de faciliter la lecture de donnees
 *   a partir du clavier. <BR>
 *   Elle definit une methode de lecture pour les types de base
 *   les plus courramment utilises (int, float, double, boolean, String).<BR>
 *   <BR>
 *   La lecture d'une valeur au clavier se fait en tapant celle-ci suivie
 *   d'un retour chariot.
 *   <BR>
 *   En cas d'erreur de lecture (par exemple un caractere a ete tape
 *   lors de la lecture d'un entier) un message d'erreur est affiche
 *   sur la console et l'execution du programme est interrompue.
 *   <BR><BR><BR>
 *   <B>exemples d'utilisation de cette classe</B><BR>
 *   <PRE>
 *      System.out.print("entrez un entier : ");
 *      System.out.flush();
 *      int i = LectureClavier.lireEntier();
 *      System.out.println("entier lu : " + i);
 *
 *      System.out.print("entrez une chaine :");
 *      System.out.flush();
 *      String s = LectureClavier.lireChaine();
 *      System.out.println("chaine lue : " + s);
 *
 *      System.out.print("entrez une reel (float) : ");
 *      System.out.flush();
 *      float f = LectureClavier.lireFloat();
 *      System.out.println("reel (float) lu : " + f);
 *
 *      System.out.print("entrez une reel (double) : ");
 *      System.out.flush();
 *      double d = LectureClavier.lireDouble();
 *      System.out.println("reel (double) lu : " + d);
 *
 *      System.out.print("entrez une reponse O/N : ");
 *      System.out.flush();
 *      boolean b = LectureClavier.lireOuiNon();
 *      System.out.println("booleen lu : " + b);
 *   </PRE>
 *
 *   @author Philippe Genoud
 *   @version 13/10/98
 */
public class LectureClavier {
    
    private static BufferedReader stdin = new BufferedReader(
            new InputStreamReader(System.in));
    
    /**
     * lecture au clavier d'un entier simple precision (int)
     * @return l'entier lu
     * @param invite une chaine d'invite 
     */
    public int lireEntier(String invite) {
        int res = 0;
        boolean ok = false;
        System.out.print(invite + " ");
        do {
            try {
                res = Integer.parseInt(stdin.readLine());
                ok = true;
            } catch (NumberFormatException nbfe) {
                System.out.println("entrez un entier");
                System.out.println(invite + " ");
            } catch (Exception e) {
                erreurEntree(e,"entier");
            }
        } while (! ok);
        return res;
    }
    
    public List<Integer> lireEntiersSansRep(String message){
    	List<Integer> res = new LinkedList<Integer>();
        boolean ok = false;
        System.out.print(message + " ");
        do {
            try {
                String restemp = stdin.readLine();
                StringTokenizer st = new StringTokenizer(restemp, " ", false);
                String i;
                int i2;
                int compt = 1;
                while(compt<=5){
                	try{
                		i = st.nextToken();
                		i2 = Integer.parseInt(i);
                		if(!res.contains(i2)){
                			res.add(i2);
                		}
                		compt++;
                	}catch(NoSuchElementException e){
                		break;
                	}
                }
                ok = true; 
            } catch (NumberFormatException nbfe) {
                System.out.println("Probleme1");
                System.out.println(message + " ");
            } catch (Exception e) {
                erreurEntree(e,"entier");
            }
        } while (! ok);
        return res;
    }
    
    /**
     * lecture au clavier d'une chaine de caracteres
     * @return la chaine lue
     */
    public String lireChaine(String invite) {
        try {
        	System.out.print(invite + " ");
            return(stdin.readLine());
        } catch (Exception e) {
            erreurEntree(e,"chaine de caracteres");
            
            return null;
        }
    }
    
    /**
     * lecture au clavier d'un reel simple precision (float)
     * @return le float lu
     * @param invite une chaine d'invite 
     */
    public float lireFloat(String invite) {
        System.out.println(invite + " ");
        try {
            return(Float.valueOf(stdin.readLine()).floatValue());
        } catch (Exception e) {
            erreurEntree(e,"reel (float)");
            
            return (float) 0.0;
        }
    }
    
    /**
     * lecture au clavier d'un reel double precision (double)
     * le double lu
     * @param invite une chaine d'invite 
     * @return le double lu
     */
    public double lireDouble(String invite) {
        System.out.println(invite + " ");
        try {
            return(Double.valueOf(stdin.readLine()).doubleValue());
        } catch (Exception e) {
            erreurEntree(e,"reel (double)");
            return 0.0;
        }
    }
    
    /**
     * en cas d'erreur lors d'une lecture, affiche sur la sortie standard
     * (System.out) un message indiquant le type de l'erreur ainsi que
     * la pile des appels de methodes ayant conduit a cette erreur.
     * @param e l'exception decrivant l'erreur.
     * @param message le message d'erreur a afficher.
     */
    private void erreurEntree(Exception e, String message) {
        System.out.println("Erreur lecture " + message);
        System.out.println(e);
        e.printStackTrace();
        System.exit(1);
    }
    
} // LectureClavier
