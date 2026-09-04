public abstract class Contenitore implements Comparable<Contenitore> {
    // OVERVIEW modella un Contenitore di liquido con capienza e quantità inserita

    private String liq;
    private double qta;
    private final double vol;

    Contenitore(String liq, double qta, double vol) throws ExceededCapacityException {
        // MODIFIES this
        // EFFECTS inizializza this

        if (liq == null)
            throw new NullPointerException("Liquido null");
        if (qta < 0)
            throw new IllegalArgumentException("Quantità < 0");
        if (vol <= 0)
            throw new IllegalArgumentException("Volume < 0");
        if (qta > vol)
            throw new ExceededCapacityException("Quantità > volume");

        this.liq = liq;
        this.qta = qta;
        this.vol = vol;

        assert repOk();
    }

    public String getLiq(){
        return this.liq;
    }

    public void versa(Contenitore c) throws IncompatibleLiquidException {
        // MODIFIES this, c
        // EFFECTS versa liquido da this a c fino alla massima capienza solo se i
        // liquidi sono compatibili (stesso liquido o il secondo è vuoto) altrimenti
        // lancia IncompatibleLiquidException

        if (c == null)
            throw new NullPointerException("C null");
        if (!(c.liq.equals(this.liq)) && !(c.liq.equals("")))
            throw new IncompatibleLiquidException("Liquidi incompatibili");

        if (this.qta == 0 || this.qta == c.vol)
            return;

        c.liq = this.liq;
        
        if (this.qta + c.qta <= c.vol) {
            c.qta = this.qta + c.qta;
            this.qta = 0;
            this.liq = "";
        }else{
            this.qta -= (c.vol - c.qta);
            c.qta = c.vol;
        }

        assert repOk();
        assert c.repOk();
    }

    public boolean repOk() {
        if (liq == null)
            return false;
        if (qta < 0)
            return false;
        if (vol <= 0)
            return false;
        if (qta > vol)
            return false;
        return true;
    }

    @Override
    public int compareTo(Contenitore o) {
        if (this.vol > o.vol)
            return 1;
        if (this.vol < o.vol)
            return -1;
        return 0;
    }

    @Override
    public String toString() {
        return "Capienza: " + this.vol + ", Liquido: " + this.liq + ", Qta: " + this.qta;
    }

}
