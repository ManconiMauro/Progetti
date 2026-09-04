public class Punto {
    //Overview:creare e progettare una classe punto formata dalle cordinate x e y immutabili
    //Un'istanza della classe `Punto` deve poter creare un nuovo punto alle coordinate x e y date;
    //deve saper restituire le proprie coordinate x e y; deve avere la capacità di calcolare la distanza con un altro punto,
    // e deve poter generare un punto nuovo con uno scostamento x e y dato

    //attributi
    private final Double x;
    private final Double y;

    //costruttore
    public Punto(){
        //Modifies:this
        //effects:inizializza un punto con x e y =0
        this.x=0.0;
        this.y=0.0;
        assert RepOk();
    }

    //costruttore
    public Punto(Double x, Double y) throws IllegalArgumentException{
        //modifies:this
        //effects:inizializza un punto dati i parametri
        //in caso uno dei valori sia nullo chiama una IllegalArgumentEcxeption
        if(x==null||y==null){
            throw new IllegalArgumentException("Valori inseriti nulli");
        }
        this.x=x;
        this.y=y;
        assert RepOk();
    }

    //getter
    public Double getX() {
        //effects:restituisce x
        return x;
    }

    //getter
    public Double getY() {
        //effects:restituisce y
        return y;
    }

    //metodo
    public Double CalcolaDistanza(Punto p1){
        //effects:ritorna la distanza tra 2 punti
        Double X=this.x-p1.getX();
        Double Y=this.y-p1.getY();
        Double somma=Math.pow(X, 2)+Math.pow(Y, 2);
        return Math.sqrt(somma);
    }
    
    //metodo
    public Punto InsertScostamento(Punto p, Double scostamento){
        //effects:ritorna un punto nuovo dato un punto e uno scostamento
        Punto point=new Punto(p.x+scostamento, p.y+scostamento);
        return point;
    }

    public boolean RepOk(){
        if(this.x==null||this.y==null){
            return false;
        }
        return true;
    }
}
