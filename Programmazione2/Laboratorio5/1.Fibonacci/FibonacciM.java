import java.util.Iterator;

public class FibonacciM implements Iterator{
    //Overview:creare un programma che dato un numero da riga di comando n,  stampi n numeri della sequenza di fibonacci

    //Attributi
    private int primo;
    private int secondo;
    public int counter;
    private final int n;

    //costruttore
    public FibonacciM(int n){
        //modifies:this
        //effects:inizializza primo a 0 e secondo a 1 così che la somma dia il primo numero di fibonacci 
        this.primo=0;
        this.secondo=1;
        this.counter=0;
        this.n=n;
    }

    //getters
    public int getPrimo() {
        return primo;
    }

    public int getSecondo() {
        return secondo;
    }

    //setter
    public void setPrimo(int primo) {
        //modifies:this
        this.primo = primo;
    }

    public void setSecondo(int secondo) {
        //modifies:this
        this.secondo = secondo;
    }

    @Override
    public boolean hasNext() {
        if(this.counter==this.n){
            return false;
        }
        return true;
    }

    @Override
    public Object next() {
        for (int i = getSecondo()+1; true; i++) {
            if(i==getPrimo()+getSecondo()){
                setPrimo(getSecondo());
                setSecondo(i);
                return i;
            }
        }
    }

    public static void main(String[] args) {
        int n=Integer.parseInt(args[0]);
        FibonacciM f=new FibonacciM(n);
        while (f.hasNext()) {
            System.out.println(f.next());
        }
    }
}
