import java.util.ArrayList;
import java.util.HashMap;
import java.util.Scanner;

public class Visita{
    public static void main(String[] args) throws IllegalArgumentException{
        ArrayList<Dottore> dottori=new ArrayList<>();
        ArrayList<Paziente> pazienti= new ArrayList<>();
        HashMap<String, Integer> visite= new HashMap<>();
        Scanner s=new Scanner(System.in);
        System.out.println("Inserisci medici nel formato `nome specializzazione parcella` (termina con CTRL+D)");
        while(s.hasNext()){
            String nome=s.next();
            String specializzazione=s.next();
            Double percella=Double.parseDouble(s.next());
            Dottore d=new Dottore(nome, specializzazione, percella);
            dottori.add(d);
        }
        System.out.println();

        Scanner scan=new Scanner(System.in);
        System.out.println("Inserisci i pazienti nel formato `nome codice` (termina con CTRL+D)");
        while(scan.hasNext()){
            String nome=scan.next();
            String codiceIdentificativo=scan.next();   
            Paziente p=new Paziente(nome,codiceIdentificativo);
            pazienti.add(p);
        }
        System.out.println();

        Scanner scanner=new Scanner(System.in);
        System.out.println("Inserisci visite nel formato `nomeDottore codicePaziente` (termina con CTRL+D)");
        while(scanner.hasNext()){
            String nomeDoc=scanner.next();
            String codiceIdentificativo=scanner.next(); 
            boolean b=true;  
            for(Dottore d:dottori){
                if(d.getNome().equals(nomeDoc)){
                    if(visite.putIfAbsent(d.getNome(), 1)!=null){
                        boolean controllo = true;
                        for(Paziente p:pazienti){
                            if(p.getCodiceIdentificativo().equals(codiceIdentificativo)){
                                int count = visite.containsKey(d.getNome()) ? visite.get(d.getNome()) : 0;
                                visite.put(d.getNome(), count+1);
                            }
                        }
                        if(!(controllo)){
                            System.out.println("Impossibile inserire la visita il codice del paziente è inesistente");
                        }
                    }
                }
            }
        }

        System.out.println();
        System.out.println("I guadagni del mese sono:");
        for (String nomeDoc : visite.keySet()) {
            for (Dottore d : dottori) {
                if(d.getNome().equals(nomeDoc)){
                    System.out.println(nomeDoc+" "+d.calcolaParcella(visite.get(nomeDoc)));
                }
            }
        }
    }
}
