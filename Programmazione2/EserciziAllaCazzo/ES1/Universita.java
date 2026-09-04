package EserciziAllaCazzo.ES1;

import java.util.ArrayList;
import java.util.Scanner;

public class Universita {
    //Overview:creare una classe università con una lista di docenti e che contengaun metodo per ritornare il docente più piccolo

    //Attributi
    ArrayList<Docente> professori;

    //costruttore
    public Universita(){
        this.professori=new ArrayList<>();
    }

    //metodo
    public void Add(Docente d){
        //effects:inserisce un docente nella classe
        this.professori.add(d);
    }

    //metodo
    public Docente etaMinima(){
        //effects:trova e ritorna il docente con l'età inferiore
        Docente prof;
        for (int i = 0; i < professori.size(); i++) {
            if(i==0){
                prof=professori.get(i);
            }else{
                Docente d=professori.get(i);
                if(d.getEta() < prof.getEta()){
                    prof=d;
                }
            }
        }
        return prof;
    }

    public static void main(String[] args) {
        Universita u=new Universita();
        Scanner s=new Scanner(System.in);
        System.out.println("Scrivi una serie di docenti in formato <nome>,<cognome>,<età>,<codice identificativo>(premere Ctrl+D per terminare)");
        while(s.hasNext()){
            String nome=s.next();
            String cognome=s.next();
            int eta=s.nextInt();
            String codice=s.next();
            Docente d=new Docente(nome, cognome, eta, codice);
            u.Add(d);
        }

        Docente prof=u.etaMinima();
        System.out.println(prof);
    }
}
