import java.util.ArrayList;
import java.util.Scanner;

public class Persona {
    //Overview:Creare una classe persona con un solo attributo, il nome e che abbia un getter e un costruttore

    //Attributi
    private String nome;

    //costruttore
    Public Persona(String nome) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza una persona con un nome e se il nome è vuoto lancia una IllegalArgumentException
        if(nome.equals("")) throw new IllegalArgumentException("Perosna inserita non valida per mancanza di nome");
        this.nome=nome;
    }

    //getter
    public String getNome() {
        return nome;
    }

    //setter
    public void setNome(String nome) {
        //modifies:this
        this.nome = nome;
    }

    public static void main(String[] args) {
        ArrayList<Dipendente> dipendenti=new ArrayList<>();
        Scanner s=new Scanner(System.in);
        System.out.println("Inserisci dipendenti nel formato `nome codice anno salario` (termina con CTRL+D)");
        while(s.hasNext()){
            String nome=s.next();
            String codiceIdentificativo=s.next();
            int annoAssunzione=Integer.parseInt(s.next());
            Double salario=Double.parseDouble(s.next());
            Dipendente d=new Dipendente(nome,salario,annoAssunzione, codiceIdentificativo);
            dipendenti.add(d);
        }

        System.out.println("Dipendenti assunti prima del 2374:");
        for (Dipendente d : dipendenti) {
            System.out.println(d.getNome);
        }
    }
}
