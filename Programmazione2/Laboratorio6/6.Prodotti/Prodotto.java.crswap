import java.util.Scanner;

public class Prodotto {
    //Overview:Creare una classe prodotto che crei un nuovo prodotto e applichi uno sconto in base ad esso

    //Attributi
    public String nome;
    public Double costo;
    public String DatoProdotto;

    //Costruttori
    public Prodotto(String nome, Double costo, String DatoProdotto) throws NotValidProdotto{
        //modifiers:this
        //Effects:costruisce la classe prodotto e lancia una NotValidProdotto in caso un valore non sia inserito
        if(nome==null) throw new NotValidProdotto("Nome nullo");
        if(costo==null) throw new NotValidProdotto("Costo non valido");
        if(DatoProdotto==null) throw new NotValidProdotto("Prodotto non valido");

        this.nome=nome;
        this.costo=costo;
        this.DatoProdotto=DatoProdotto;
    }

    //setter
    public void setNome(String nome) {
        this.nome = nome;
    }

    public void setCosto(Double costo) {
        this.costo = costo;
    }

    public void setDatoProdotto(String datoProdotto) {
        DatoProdotto = datoProdotto;
    }

    //getter
    public String getNome() {
        return nome;
    }

    public Double getCosto() {
        return costo;
    }

    public String getDatoProdotto() {
        return DatoProdotto;
    }

    public int getSconto(){
        return 5;
    }

    //metodi
    public Double Sconto(){
        //effects:restituisce il prezzo scontato in base al prodotto
        double sot=(costo*getSconto())/100;
        return costo-sot;
    }

    @Override
    public String toString() {
        return "Prezzo con sconto del "+this.getSconto()+"%: "+this.costo;
    }

    //testing
    public static void main(String[] args) throws NotValidProdotto{
        Scanner s = new Scanner(System.in);
        System.out.println("Inserisci un prodotto nel formato: `<nome> <costo> alimentare <datascadenza>` oppure `<nome> <costo> nondeperibile riciclabile\nonriciclabile`");
        while(s.hasNext()){
            Prodotto prodotto=null;
            String nome=s.next();
            Double costo=Double.parseDouble(s.next());
            String tipo=s.next();
            String DatoProdotto=s.next();
            try {
                if (tipo.equals("alimentare")) {
                    prodotto=new ProdottoAlimentare(nome, costo, DatoProdotto);
                }else if (tipo.equals("nondeperibile")) {
                    prodotto=new ProdottoNonDeperibile(nome, costo, DatoProdotto);
                }else{
                    throw new NotValidProdotto("Tipo di prodotto inserito non valido");
                }
            } catch (NotValidProdotto n) {
                System.out.println(n);
            }
            prodotto.setCosto(prodotto.Sconto());

            System.out.println(prodotto);
        }
    }
}
