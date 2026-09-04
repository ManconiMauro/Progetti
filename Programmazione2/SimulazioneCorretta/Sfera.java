public class Sfera extends Contenitore {
    // OVERVIEW modella un contenitore sfera

    double r;

    public Sfera(String liq, double qta, double r) throws ExceededCapacityException {
        // MODIFIES this
        // EFFECTS inizializza nuovo contenitore Sfera
        super(liq, qta, Math.PI * Math.pow(r, 3) * (4 / 3));
        if (r <= 0)
            throw new IllegalArgumentException("r <= 0");

        this.r = r;
    }

    @Override
    public String toString() {
        return "Sfera - r: " + this.r + "(" + super.toString() + ")";
    }

    
}
