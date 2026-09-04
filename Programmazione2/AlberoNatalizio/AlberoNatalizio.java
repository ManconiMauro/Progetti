import java.util.ArrayList;
import java.util.Iterator;
import java.util.Scanner;

public class AlberoNatalizio implements Iterable<Decorazione> {
    //Overview:creare una classe Albero natalizio che contenga un carico massimo e una potenzaElettrica
    //la classe conterrà una lista di Decorazioni diverse
    //quando si cerca di inserire una decorazione ma questa supera il carico massimo allora verrà chiamata una WeightReachedException
    //inoltre se la decorazione che si cerca di aggiungere è un puntale ed è già stato inserito uno allora verrà chiamata una TopperExistsException

    //Attributi
    //ho deciso di mettere tutti gli attributi di AlberoNatalizio public perchè saranno modificati continuamente
    public double caricoMassimo;
    public double potenzaElettrica;
    ArrayList<Decorazione> decorazioni;

    //constructor
    public AlberoNatalizio(double caricoMassimo,double potenzaElettrica) throws IllegalArgumentException{
        //modifies:this.caricoMassimo,this.potenzaElettrica
        //effects:inizializza un orìggetto albero di natale
        //se il carico o la potenza sono <=0 lancia una IllegalArgumentException
        if(caricoMassimo<=0||potenzaElettrica<=0){
            throw new IllegalArgumentException("Carico o potenza inseriti non validi");
        }
        this.caricoMassimo=caricoMassimo;
        this.potenzaElettrica=potenzaElettrica;
        this.decorazioni=new ArrayList<>();
    }

    //methods
    public void Insert(Decorazione d) throws WeightReachedException{
        //modifies:this.caricoMassimo,this.potenzaElettrica,this.decorazioni
        //effects:inserisce nella lista una decorazione, modificando caricoMassimo
        //se il peso inserito super il caricoMassimo allora laceremo una WeightReachedException
        if(this.caricoMassimo-d.getPeso()<0){
            throw new WeightReachedException("Non si può aggiungere: Carico superato");
        }
        this.caricoMassimo-=d.getPeso();
        decorazioni.add(d);
        assert RepOk();
    }

    //methods
    public void AccendiLuci(){
        //modifies:this.potenzaElettrica
        //effects:itera su tutte le decorazioni e in caso la decorazione sia elettrica
        //controlla se l'albero riesce ad accenderla in caso riesce chiameremo la funzione interruttore 
        Iterator<Decorazione> iter=iterator();
        while(iter.hasNext()){
            if(iter.next() instanceof DecorazioneElettrica){
                Decorazione d=iter.next();
                DecorazioneElettrica de=(DecorazioneElettrica)d;
                if(this.potenzaElettrica-de.getPotenza()>=0){
                    this.potenzaElettrica-=de.getPotenza();
                    de.Interruttore();
                }
            }
        }
    }

    //methods
    public void PuntalePresente() throws TopperExistsException{
        //effects:iteraa tutta la lista di decorazioni per trovare se una di queste è un puntale
        //in caso trova un puntale presnte lancia una TopperExistsException
        Iterator<Decorazione> iter=iterator();
        while(iter.hasNext()){
            if(iter.next() instanceof Puntale){
                throw new TopperExistsException("Non si può aggiungere: Puntale già aggiunto");
            }
        }
    }

    @Override
    public Iterator<Decorazione> iterator(){

        //ho deciso di utilizzare l'iteratore dell'arraylist per la classe locale
        return new Iterator<Decorazione>() {
            //Attributi
            Iterator<Decorazione> i=decorazioni.iterator();

            @Override
            public boolean hasNext() {
                return i.hasNext();
            }

            @Override
            public Decorazione next() {
                return i.next();
            }
            
        };
    }

    //invariante di rapprresentazione
    public boolean RepOk(){
        if(caricoMassimo<=0||potenzaElettrica<=0){
            return false;
        }
        if(decorazioni==null){
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        //effects:converte un oggetto albero in una stringa da visualizzare
        return "Albero (Carico: "+this.caricoMassimo+", Potenza: "+this.potenzaElettrica+")";
    }

    public static void main(String[] args) {
        double caricoMassimo=Double.parseDouble(args[0]);
        double potenzaElettrica=Double.parseDouble(args[1]);
        AlberoNatalizio a=new AlberoNatalizio(caricoMassimo, potenzaElettrica);
        Scanner s=new Scanner(System.in);
        while(s.hasNext()){
            String dec=s.next();
            if(dec.equals("Decorazione")){
                String nome=s.next();
                double peso=s.nextDouble();
                Decorazione d=new Decorazione(nome, peso);
                try {
                    a.Insert(d);
                } catch (WeightReachedException e) {
                    System.out.println(e);
                }
            }else if(dec.equals("DecorazioneElettrica")){
                String nome=s.next();
                double peso=s.nextDouble();
                double potenza=s.nextDouble();
                DecorazioneElettrica d=new DecorazioneElettrica(nome, peso, potenza);
                try {
                    a.Insert(d);
                } catch (WeightReachedException e) {
                    System.out.println(e);
                }
            }else if(dec.equals("Puntale")){
                String nome=s.next();
                double peso=s.nextDouble();
                Puntale d=new Puntale(nome, peso);
                try {
                    a.PuntalePresente();
                } catch (TopperExistsException t) {
                    System.out.println(t);
                }
                try {
                    a.Insert(d);
                } catch (WeightReachedException e) {
                    System.out.println(e);
                }
            }else{
                System.out.println("Decorazione inserita non esistente");
            }
        }
        //non utilizzo il metodo accendiLuci pur avendoloimplementato perchè a runtime mi da problemi col casting
        //a.AccendiLuci();
        Iterator<Decorazione> iter=a.iterator();
        System.out.println(a);
        while(iter.hasNext()){
            System.out.println(iter.next());
        }
    }
}
