import java.util.ArrayList;
import java.util.Scanner;

public class Percorso {
    //Overview:creare una classe percorso che contenga vari punti
    //la classe deve poter rimouvere l'ultimo punto inserito e calcolare la lunghezza totale del percorso
    
    //Attributi
    ArrayList<Punto> percorso;

    //costruttore
    public Percorso(){
        //modifies:this
        //effects:inizializza la lista
        this.percorso=new ArrayList<>();
    }

    //metodo
    public void Insert(Punto p){
        //modifies:this
        //effects:aggiunge un nuovo punto al percorso
        this.percorso.add(p);
    }

    //metodo
    public void DeleteLast(){
        //modifies:this
        //effects:rimuove l'ultimo punto aggiunto alla lista
        this.percorso.remove(this.percorso.size()-1);
    }

    //metodo
    public Double DistanzaPercorso(){
        //effects:calcola la distanza totale sommando le distanze tra i vari punti e la restituisce
        Double distanza=0.0;
        for (int i = 0; i < percorso.size()-1; i++) {
            Punto p=percorso.get(i);
            distanza+=p.CalcolaDistanza(percorso.get(i+1));
        }
        return distanza;
    }

    public static void main(String[] args) {
        Percorso p=new Percorso();
        Scanner s=new Scanner(System.in);
        while (s.hasNext()) {
            Double x=s.nextDouble();
            Double y=s.nextDouble();
            Punto point=new Punto(x, y);
            p.Insert(point);
            System.out.println(point.getX()+" "+point.getY());
        }

        System.out.println();
        for (int i = 0; i < p.percorso.size()-1; i++) {
            Punto point=p.percorso.get(i);
            System.out.println("Tratto "+i+1+": distanza "+point.CalcolaDistanza(p.percorso.get(i+1)));
        }
        System.out.println("Totale: "+p.DistanzaPercorso());
    }
}
