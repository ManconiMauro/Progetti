import java.util.ArrayList;
import java.util.Iterator;
import java.util.Scanner;

public class Rubrica implements Iterator<Contatto>{
    //Overview:creare una classe rubrica che contenge una serie di Contatti

    //Attributi
    ArrayList<Contatto> rubrica;
    int count=0;

    //costruttore
    public Rubrica(){
        this.rubrica=new ArrayList<>();
    }

    //metodo
    public void Insert(Contatto c){
        //modifies:this
        //effects:inserisce un nuovo contatto in rubrica
        this.rubrica.add(c);
    }

    //metodo
    public void Delete(Contatto c){
        //modifies:this
        //effects:elimina un contatto dalla rubrica
        this.rubrica.remove(c);
    }

     @Override
    public boolean hasNext() {
       if(count<rubrica.size()){
            return true;
        }
        return false;
    }

    @Override
    public Contatto next() {
        Contatto r=rubrica.get(count);
        count++;
        return r;
    }

    public static void main(String[] args) {
        Rubrica r=new Rubrica();
        System.out.println("Inserisci Contatti nel formato <nome><cognome><numero><email>(ctrl+d per terminare)");
        Scanner s=new Scanner(System.in);
        while(s.hasNext()){
            String nome=s.next();
            String cognome=s.next();
            String numero=s.next();
            String email=s.next();
            Contatto c=new Contatto(nome, cognome, numero, email);
            r.Insert(c);
        }

        Scanner sc=new Scanner(System.in);
        while(true){
            System.out.println("Scriver 1:per vedere l'intera rubrica\n 2:per eliminare un contatto\n 3:per aggiungere un contatto\n 4:per aggiungere un numero o un email ad un contatto\n premere un qualsiasi altro numero per uscire");
            int num=sc.nextInt();
            if(num==1){
                while(r.hasNext()){
                    System.out.println(r.next());
                }
            }else if(num==2){
                System.out.println("Dammi nome e cognome del contatto");
                    String nome=sc.next();
                    String cognome=sc.next();
                    System.out.println(nome+" "+cognome);
                    while(r.hasNext()){
                        Contatto del=r.next();
                        if(nome.equalsIgnoreCase(del.getNome())){
                            if(cognome.equalsIgnoreCase(del.getCognome())){
                                r.Delete(del);
                            }
                        }
                    }
            }else if(num==3){
                System.out.println("Dammi i dati del contatto nel formato di prima(<nome><cognome><numero><email>)");
                    String nom=sc.next();
                    String cognom=sc.next();
                    String numero=sc.next();
                    String email=sc.next();
                    Contatto contact=new Contatto(nom, cognom, numero, email);
                    r.Insert(contact);
            }else{
                break;
            }
        }
    }
}
