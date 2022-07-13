public class Livro extends ItemDeEstoque{
    String autor;

    public Livro(String nome, float preco, int qtdEstoque, int estoqueMaximo, String autor) {
        super(nome,preco,qtdEstoque,estoqueMaximo);
        this.autor = autor;
    }

    @Override
    public String toString() {
        return "Livro{" +
                "id='" + id + '\'' + "nome=" + nome + '\'' + "preco=" + preco + "quantidade em estoque="
                +qtdEstoque + '\'' +"estoque maximo=" + '\''+ estoqueMaximo + "autor=" +autor+'\''+
                '}';
    }
}
