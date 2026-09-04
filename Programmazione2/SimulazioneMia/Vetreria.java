import java.util.ArrayList;
import java.util.Collection;
import java.util.Iterator;
import java.util.Scanner;

public class Vetreria implements Iterable<Contenitore> {
    //Overview: creare una classe vetreria che implementi una lista di contenitori

    //Attributi
    ArrayList<Contenitore> vet;

    //constructor
    public Vetreria(){
        //Modifies:this
        //Effects:Inizializza la vetreria
        vet=new ArrayList<>();
        assert RepOK();
    }

    //methods
    public void Insert(Contenitore c){
        //Modifies:this
        //Effects:Inserisce un nuovo contenitore dentro vetreria
        vet.add(c);
        assert RepOK();
    }

    public void Remove(Contenitore c){

    }

    //methods
    public Vetreria TrovaLiquido(String liquido){
        //Modifies:this
        //Effects:crea una nouva vetreria con solo contenitori che contengono un certo liquido e la ritorna
        //cancellando i contenitori nell vecchia vatreria che aggiunge alla nuova
        Vetreria v=new Vetreria();
        Iterator<Contenitore> i=vet.iterator();
        for (Contenitore c : vet) {
            if(liquido.equals(c.getLiquido())){
                v.Insert(c);
                //vet.remove(c);
            }
        }
        return v;
    }

    //methods
    public void Distribuzione(Contenitore c1) throws IncompatibleLiquidsException{
        //Modifies this
        //Effects:cicla tutta la vetreria e se 2 Contenitore hanno lo stesso liquido
        //una volta controllato controlla quale contenitore ha maggiore liquido e 
        //insersce dentro quello maggiore la differenza tra i 2 liquidi 
        //se la somma della quantita dei 2 liquidi supera la quantita allora si inserià tutto dentro al primo contenitore e la differenza dentro il secondo
        //se i 2 liquidi sono diversi lancia una IncompatibleLiquidsException
        for (Contenitore c2 : vet) {
                if(!(c1.getLiquido().equals(c2.getLiquido()))){
                    throw new IncompatibleLiquidsException("Liquidi diversi");
                }
                if(c1.compareTo(c2)>0){
                    double diff=(c1.getQuantita()+c2.getQuantita())-c1.GetCapienza();
                    if(diff<0){
                        double q=c1.getQuantita()+c2.getQuantita();
                        System.out.println(q);
                        c1.setQuantita(q);
                        c2.setQuantita(0);
                    }else{
                        c1.setQuantita(c1.GetCapienza());
                        c2.setQuantita(diff*-1);
                    }
                }else if(c1.compareTo(c2)<0){
                    double diff=(c1.getQuantita()+c2.getQuantita())-c2.GetCapienza();
                    if(diff<0){
                        c2.setQuantita(c1.getQuantita()+c2.getQuantita());
                        c1.setQuantita(0);
                    }else{
                        c2.setQuantita(c1.GetCapienza());
                        c1.setQuantita(diff*-1);
                    }
                }
        }
    }

    @Override
    public Iterator<Contenitore> iterator(){
        //creo un iteratore usando l'iteratore dell'ArrayList e importando i metodi
        //hodeciso di fare il metodo remove solo dentro l'iteratore perchè verra usato solo in una funzione
        return new Iterator<Contenitore>(){
            Iterator<Contenitore> i=vet.iterator();

            @Override
            public boolean hasNext() {
                // TODO Auto-generated method stub
                return i.hasNext();
            }

            @Override
            public Contenitore next() {
                // TODO Auto-generated method stub
                return i.next();
            }

        };
    }

    //invariante di rappresente
    public boolean RepOK(){
        if(vet==null){
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        String ret="Vetreria con:\n";
        for (Contenitore contenitore : vet) {
            ret+=contenitore+"\n";
        }
        return ret;
    }

    public static void main(String[] args) {
        Vetreria v=new Vetreria();
        Scanner s=new Scanner(System.in);
        while(s.hasNext()){
            String liquido=s.next();
            double quantita=s.nextDouble();
            String tipo=s.next();
            switch (tipo) {
                case "Sfera":
                    double parametro=s.nextDouble();
                    try {
                        Sfera sfera=new Sfera(liquido, quantita, parametro);
                        v.Insert(sfera);
                    } catch (IllegalArgumentException e) {
                        e.printStackTrace();
                    } catch (ExceededCapacityException e) {
                        e.printStackTrace();
                    }
                    break;
                case "Cilindro":
                    double parametro1=s.nextDouble();
                    double parametro2=s.nextDouble();
                    try {
                        Cilindro cil=new Cilindro(liquido, quantita, parametro1,parametro2);
                        v.Insert(cil);
                    } catch (IllegalArgumentException e) {
                        e.printStackTrace();
                    } catch (ExceededCapacityException e) {
                        e.printStackTrace();
                    }
                    break;
                case "Cuboide":
                    double parametroC=s.nextDouble();
                    double parametroC2=s.nextDouble();
                    double parametroC3=s.nextDouble();
                    try {
                        Cuboide cub=new Cuboide(liquido, quantita, parametroC,parametroC2, parametroC3);
                        v.Insert(cub);
                    } catch (IllegalArgumentException e) {
                        e.printStackTrace();
                    } catch (ExceededCapacityException e) {
                        e.printStackTrace();
                    }
                    break;
                default:
                    break;
            }
        }
        System.out.println(v);
        //non sono riuscito a riodinare la lista probabilmente perche ho sbagliato il comparator
        //Collection.sort(v);
        ArrayList<Vetreria> lista=new ArrayList<>();
        Iterator<Contenitore> i=v.iterator();
        while (i.hasNext()) {
            Contenitore c=i.next();
            Vetreria vet=v.TrovaLiquido(c.getLiquido());
            vet.Distribuzione(c);
            lista.add(vet);
        }  
        for (int index = 0; index < lista.size(); index++) {
            System.out.println(lista.get(index));
        }  
    }
}
