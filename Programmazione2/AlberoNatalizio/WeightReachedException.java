public class WeightReachedException extends Exception {
    
    public WeightReachedException(){
        super("Carico peso massimo dell'albero raggiunto");
    }

    public WeightReachedException(String s){
        super(s);
    }
}
