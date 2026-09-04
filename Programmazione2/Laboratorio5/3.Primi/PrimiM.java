import java.util.Iterator;

public class PrimiM implements Iterator<Integer> {
    //Overview:creare un iteratore che restituisca i numeri primi

    //Attributi
    public Integer primo;
    public int count;
    public Integer n;

    //costruttori
    public PrimiM(){
        this.primo=1;
        this.n=0;
        this.count=0;
    }

    public PrimiM(Integer n){
        this.primo=1;
        this.n=n;
        this.count=0;
    }

    @Override
    public boolean hasNext() {
        if (count==n) {
            return false;
        }
        return true;
    }

    @Override
    public Integer next() {
        for (int i = this.primo+1; true; i++) {
            if(i==2||i==1){
                this.count++;
                this.primo=i;
                return primo;
            }
            boolean b = true; 
            for (int j = 2; j < i; j++) {
                if(i%j==0){
                    b=false;
                }
            }
            if (b) {
                this.count+=2;SS
                primo=i;
                return primo;
            }
        }
    }

    public static void main(String[] args) {
        String numero=args[0];
        Integer n=Integer.parseInt(numero);
        PrimiM p = new PrimiM(n);
        while(p.hasNext()){
            System.out.println(p.next());
        }
    }
}
