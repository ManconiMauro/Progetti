public class Cilindro extends Contenitore {
    // OVERVIEW modella un contenitore sfera

    private final double h;
    private final double r;

    Cilindro(String liq, double qta, double h, double r)
            throws ExceededCapacityException {
        super(liq, qta, h * Math.PI * (r * r));

        if (h <= 0)
            throw new IllegalArgumentException("a <= 0");
        if (r <= 0)
            throw new IllegalArgumentException("b <= 0");

        this.h = h;
        this.r = r;
    }

    @Override
    public String toString() {
        return "Cilindro - r: " + this.r + ", h: " + this.h + " (" + super.toString() + ")";
    }

}
