public class NotValidProdotto extends Exception {
    public NotValidProdotto(){
        super("Prodotto inserito non valido");
    }

    public NotValidProdotto(String s){
        super(s);
    }
}
