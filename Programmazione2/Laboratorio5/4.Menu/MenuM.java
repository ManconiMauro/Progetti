import java.util.ArrayList;
import java.util.Iterator;

public class MenuM implements Iterable<PiattoM>{
    //overview:modellare una classe menu con una serie di piatti con metodi per aggiungere e togliere i piatti e modificare i piatti
    //generare un iteratore per vedere tutti i piatti

    ArrayList<PiattoM> menu;

    //costruttore
    public MenuM(){
        //modifies:this
        this.menu=new ArrayList<>();
    }

    //metodi
    public void Insert(String nome, String tipo, Double costo){
        //modifies:this
        //effects:inserisce un nouvo piatto nel menu
        PiattoM p = new PiattoM(nome, tipo, costo);
        menu.add(p);
    }

    public void Delete(String nome) {
        //modifies:this
        //effects:elimina un piatto dal menu
        for (PiattoM p : menu) {
            if(p.nome.equals(nome)){
                menu.remove(p);
            }
        }
    }

    public Iterator<PiattiM> iterator() {

    }
}