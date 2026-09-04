public class Cuboide extends Contenitore {
    // OVERVIEW modella un contenitore sfera

    private final double a;
    private final double b;
    private final double c;

    Cuboide(String liq, double qta, double a, double b, double c)
            throws ExceededCapacityException {
        super(liq, qta, a * b * c);

        if (a <= 0)
            throw new IllegalArgumentException("a <= 0");
        if (b <= 0)
            throw new IllegalArgumentException("b <= 0");
        if (c <= 0)
            throw new IllegalArgumentException("c <= 0");

        this.a = a;
        this.b = b;
        this.c = c;
    }

    @Override
    public String toString() {
        return "Cuboide - a: " + this.a + ", b: " + this.b + ", c: " + this.c + "(" + super.toString() + ")";
    }

}
