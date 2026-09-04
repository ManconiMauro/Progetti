import java.util.ArrayList;
import java.util.Iterator;
import java.util.Scanner;

public class Vetriera implements Iterable<Contenitore> {
    //Overview:progettare una vetriera che contiene vari Contenitori la classe conterrà una lista di contenitori
    //conterrà pure un iteratore che retituisce tutti i contenitori in ordine decrescente
    //poi la vetriera avrà anche un iteratore che restituisce una lista dei contenitori che contengono un certo liquido

    //Attributi
    ArrayList<Contenitore> vetriera;

    //costruttore
    public Vetriera(){
        //modifies:this
        //effects:inizializza l'aaralist
        this.vetriera=new ArrayList<>();
    }

    //metodi
    public void Insert(Contenitore c){
        //modifies:this
        this.vetriera.add(c);
    }

    public void Delete(Contenitore c){
        //modifies:this
        this.vetriera.remove(c);
    }

    public void OrdinaLista(){
        for (int i = 0; i < vetriera.size(); i++) {
            for (int j = vetriera.size(); j > i; j--) {
                if(vetriera.get(j).CalcolaVolume()<vetriera.get(i).CalcolaVolume()){
                    Contenitore c=vetriera.get(i);
                    vetriera.set(i, vetriera.get(j));
                    vetriera.set(j, c);
                }
            }
        }
    }

    public Iterator<Contenitore> iterator(){
        //Effects:restituisce la lista di contenitori

        return new Iterator<Contenitore>(){
            //Attributi
            int counter=0;

            @Override
            public boolean hasNext() {
                if(counter<vetriera.size()){
                    return true;
                }
                return false;
            }

            @Override
            public Contenitore next() {
                Contenitore c=vetriera.get(counter);
                counter++;
                return c;
            }
        
        };
    }

    public static void main(String[] args) throws IllegalArgumentException, ExceededCapacityException {
        Vetriera v=new Vetriera();
        Scanner s = new Scanner(System.in);
        Contenitore container=null;
        while(s.hasNext()){
            String nomeLiquido=s.next();
            Double quantitaLiquido=Double.parseDouble(s.next());
            String tipoContenitore=s.next();
            if(tipoContenitore.equals("Cuboide")){
                Double a=Double.parseDouble(s.next());
                Double b=Double.parseDouble(s.next());
                Double c=Double.parseDouble(s.next());
                try {
                    container=new Cuboide(nomeLiquido, quantitaLiquido, tipoContenitore, a, b, c);
                    container.TrovaCapacita();
                    v.Insert(container);
                } catch(ExceededCapacityException e) {
                    System.out.println(e);
                }
            }else if(tipoContenitore.equals("Sfera")) {
                Double r=Double.parseDouble(s.next());
                try {
                    container=new Sfera(nomeLiquido, quantitaLiquido, tipoContenitore, r);
                    container.TrovaCapacita();
                    v.Insert(container);
                } catch (ExceededCapacityException e) {
                    System.out.println(e);
                }
            }else if(tipoContenitore.equals("Cilindro")){
                Double r=Double.parseDouble(s.next());
                Double h=Double.parseDouble(s.next());
                try {
                    container= new Cilindro(nomeLiquido, quantitaLiquido, tipoContenitore, r, h);
                    container.TrovaCapacita();
                    v.Insert(container);
                } catch (ExceededCapacityException e) {
                    System.out.println(e);
                }
            }else{
                throw new IllegalArgumentException("Contenitore inserito non esistente nella vetriera");
            }
        }

        System.out.println("Vetreria con:");
        v.OrdinaLista();
        Iterator<Contenitore> i=v.iterator();
        while(i.hasNext()){
            Contenitore c=i.next();
            System.out.println(c.tipoContenitore+": (capienza:"+c.CalcolaVolume()+" , contenuto: "+c.quantitaLiquido+" , liquido: "+c.nomeLiquido+")");
        }
    }
}
