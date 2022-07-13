public class CD extends ItemDeEstoque{
    int nFaixas;

    public CD(String nome, float preco, int qtdEstoque, int estoqueMaximo, int nFaixas) {
        super(nome,preco,qtdEstoque,estoqueMaximo);
        this.nFaixas = nFaixas;
    }

    @Override
    public String toString() {
        return "Livro{" +
                "id='" + id + '\'' + "nome=" + nome + '\'' + "preco=" + preco + "quantidade em estoque="
                +qtdEstoque + '\'' +"estoque maximo=" + '\''+ estoqueMaximo + "numero de faixas=" +'\''+nFaixas+
                '}';
    }
}
