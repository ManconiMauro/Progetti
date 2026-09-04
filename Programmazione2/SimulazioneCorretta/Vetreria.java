import java.util.ArrayList;
import java.util.Collections;
import java.util.HashSet;
import java.util.Iterator;
import java.util.Scanner;

public class Vetreria implements Iterable<Contenitore> {

    ArrayList<Contenitore> l;

    Vetreria() {
        // MODIFIES this
        // EFFECTS inizializza una vetreria

        this.l = new ArrayList<>();
    }

    public void aggiungi(Contenitore c) throws NullPointerException {
        // MODIFIES this
        // EFFECTS aggiunge c a this

        if (c == null)
            throw new NullPointerException("c null");
        l.add(c);
        assert repOk();
    }

    public void sort() {
        // MODIFIES this
        // EFFECTS riordina this dal contenitore più grande al più piccolo (in base al
        // volume)
        Collections.reverse(this.l);
        // Posso usare anche this.l.sort();
    }

    public Vetreria estrai(String t) {
        // MODIFIES this
        // EFFECTS estrae da this tutti i contenitori con liquido t e i mette in una
        // nuova Vetreria. Restituisce la nuova vetreria

        Vetreria v = new Vetreria();

        // PRIMO METODO
        Iterator<Contenitore> i = l.iterator();
        while (i.hasNext()) {
            Contenitore c = i.next();
            if (c.getLiq().equals(t)) {
                v.aggiungi(c);
                i.remove();
            }
        }

        // SECONDO METODO
        // MAI RIMUOVERE DURANTE UN CICLO

        /*
         * for (Contenitore c : l) {
         * if (c.getLiq().equals(t))
         * v.aggiungi(c);
         * }
         * this.l.removeAll(l);
         * 
         */

        return v;

    }

    public void ottimizza() {
        // MODIFIES this
        // EFFECTS: riordina la vetreria e ottimizza riempiendo dai contenitori più
        // piccoli a quelli più grandi

        this.sort();

        Contenitore last = null;

        for (Contenitore c : this.l) {
            if (last != null)
                c.versa(last);

            last = c;
        }

    }

    @Override
    public Iterator<Contenitore> iterator() {
        // QUESTA E' UNA CLASSE ANONIMA
        return new Iterator<Contenitore>() {

            Iterator<Contenitore> i = l.iterator();

            @Override
            public boolean hasNext() {
                return i.hasNext();
            }

            @Override
            public Contenitore next() {
                return i.next();
            }

        };
    }

    @Override
    public String toString() {
        String out = "";

        for (Contenitore c : l)
            out += c + "\n";
        return out;
    }

    public boolean repOk() {
        for (Contenitore c : l)
            if (c == null || !(c.repOk()))
                return false;
        return true;

    }

    public static void main(String[] args) {
        Scanner s = new Scanner(System.in);

        Vetreria v = new Vetreria();

        HashSet<String> h = new HashSet<>();

        while(s.hasNext()){
            String liq = s.next();
            h.add(liq);
            double qta = s.nextDouble();
            Contenitore c = null;
            String cont = s.next();

            try {
                switch (cont.toLowerCase()) {
                    case "cuboide":
                        c = new Cuboide(liq, qta, s.nextDouble(), s.nextDouble(), s.nextDouble());
                        break;
                    case "cilindro":
                        c = new Cilindro(liq, qta, s.nextDouble(), s.nextDouble());
                        break;
                    case "sfera":
                        c = new Sfera(liq, qta, s.nextDouble());
                        break;
                }
            } catch (ExceededCapacityException e) {
                System.out.println(e.getMessage());
                System.exit(1);
            }
            v.aggiungi(c);
        }
        System.out.println(v);

        ArrayList<Vetreria> vl = new ArrayList<>();

        for (String liq : h) {
            vl.add(v.estrai(liq));
        }
        for (Vetreria vet : vl) {
            vet.ottimizza();
            System.out.println(vet);
        }
    }

}