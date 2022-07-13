public class DVD extends ItemDeEstoque{
    float duracao;

    public DVD(String nome, float preco, int qtdEstoque, int estoqueMaximo, float duracao) {
        super(nome,preco,qtdEstoque,estoqueMaximo);
        this.duracao = duracao;
    }

    @Override
    public String toString() {
        return "Livro{" +
                "id='" + id + '\'' + "nome=" + nome + '\'' + "preco=" + preco + "quantidade em estoque="
                + qtdEstoque + '\'' + "estoque maximo=" + '\'' + estoqueMaximo + "duracao=" + duracao + '\''
                + '}';
    }
}
