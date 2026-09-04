import java.util.ArrayList;
import java.util.Iterator;

public class Contatto implements Iterable<String>{
    //Overview:modella e implementare una classe che rappresenti un contatto di una rubrica
    //uesta classe conterrà nome e congome del contatto e 2 liste una per i numeri di telefono e una per le email

    //Attributi
    private String nome;
    private String cognome;
    ArrayList<String> numeri;
    ArrayList<String> email;

    /* 
    //costruttore
    public Contatto(String nome,String cognome,String numero) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza un contatto con nome cognome e numero di telefono
        //se il nome o il cognome sono nulli o vuoti lancia una IllegalArgumentException
        //se il numero è negativo lancia un altra IllegalArgumentException
        if(nome.equals("")||nome==null||cognome.equals("")||cognome==null){
            throw new IllegalArgumentException("Nome o cognome, nulli o vuoti");
        }
        if(numero.equals("")||numero==null){
            throw new IllegalArgumentException("Numero inserito non valido");
        }
        this.nome=nome;
        this.cognome=cognome;
        this.numeri=new ArrayList<>();
        this.email=new ArrayList<>();
        this.numeri.add(numero);
        assert RepOk();
    }*/

    //costruttore
    public Contatto(String nome,String cognome,String numero,String email) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza un contatto con nome cognome e numero di telefono
        //se il nome o il cognome sono nulli o vuoti lancia una IllegalArgumentException
        //se il numero è negativo lancia un altra IllegalArgumentException
        if(nome.equals("")||nome==null||cognome.equals("")||cognome==null){
            throw new IllegalArgumentException("Nome o cognome, nulli o vuoti");
        }
        if(numero.equals("")||numero==null) {
            throw new IllegalArgumentException("Numero inserito non valido");
        }
        this.nome=nome;
        this.cognome=cognome;
        this.numeri=new ArrayList<>();
        this.email=new ArrayList<>();
        this.numeri.add(numero);
        this.email.add(email);
        assert RepOk();
    }

    //getter
    public String getNome() {
        //effects:restituisce il nome
        return nome;
    }

    //setter
    public void setNome(String nome) {
        //modifies:this.nome
        //effects:modifica il nome
        this.nome = nome;
        assert RepOk();
    }

    //getter
    public String getCognome() {
        //effects:restituisce il cognome
        return cognome;
    }

    //setter
    public void setCognome(String cognome) {
        //modifies:this.cognome
        //effects:modifica i cognome
        this.cognome = cognome;
        assert RepOk();
    }

    //getter
    public ArrayList<String> getNumeri() {
        //effects:restituisce i numeri di telefono
        return numeri;
    }

    //getter
    public ArrayList<String> getEmail() {
        //effects:restuituisce le email del contatto
        return email;
    }

    //non ho fatto i setter delle due liste perchè sarebbe stato complicato dato che sarebbe stato lungo e nell'esercizio non è richiesto

    public Iterator<String> IteraNumeri(){
        
        class IteraNumeri implements Iterator<String>{
            //Overview:creare un iteratore per la lista di numeri

            //Attributi
            int count=0;
            String num;

            @Override
            public boolean hasNext() {
                if(count<numeri.size()){
                    num=numeri.get(count);
                    return true;
                }
                return false;
            }

            @Override
            public String next() {
                count++;
                return num;
            }

        }

        return new IteraNumeri();
    }

     public Iterator<String> IteraEmail(){
        
        class IteraEmail implements Iterator<String>{
            //Overview:creare un iteratore per la lista di email

            //Attributi
            int count=0;
            String mail;

            @Override
            public boolean hasNext() {
                if(count<email.size()){
                    mail=email.get(count);
                    return true;
                }
                return false;
            }

            @Override
            public String next() {
                count++;
                return mail;
            }

        }

        return new IteraEmail();
    }

    public boolean RepOk(){
        if(nome==null||nome.equals("")||cognome==null||cognome.equals("")){
            return false;
        }
        if(numeri.size()<=0||email.size()<=0){
            return false;
        }
        return true;
    }

    @Override
    public String toString() {
        String stampa=this.nome+" "+this.cognome+"\n tel: ";
        Iterator<String> iter=IteraNumeri();
        while(iter.hasNext()){
            stampa+=iter.next()+", ";
        }
        stampa+="\n email: ";
        Iterator<String> ite=IteraEmail();
        while(ite.hasNext()){
            stampa+=ite.next()+", ";
        }
        return stampa;
    }

    @Override
    public Iterator<String> iterator() {
        // TODO Auto-generated method stub
        return null;
    }
}