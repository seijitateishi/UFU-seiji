public class ItemDeEstoque {
    static int count = 0;
    int id;
    String nome;
    float preco;
    int qtdEstoque;
    int estoqueMaximo;

    public int getId() {
        return id;
    }

    private void setId() {
        this.id = count++;
    }

    public void setNome(String nome,float preco,int qtdEstoque,int estoqueMaximo) {
        this.nome = nome;
        this.preco = preco;
        this.qtdEstoque = qtdEstoque;
        this.estoqueMaximo = estoqueMaximo;
    }

    public float getPreco() {
        return preco;
    }

    public int getQtdEstoque() {
        return qtdEstoque;
    }

    public int getEstoqueMaximo() {
        return estoqueMaximo;
    }

    public String getNome() {
        return nome;
    }

    public ItemDeEstoque(String nome,float preco,int qtdEstoque,int estoqueMaximo) {
        setId();
        this.nome = nome;
        this.preco = preco;
        this.qtdEstoque = qtdEstoque;
        this.estoqueMaximo = estoqueMaximo;

    }

    public void abaixaEstoque(int demanda) throws EstoqueBaixoException {
        if (this.qtdEstoque - demanda < 0) {
            throw new EstoqueBaixoException(qtdEstoque, demanda);
        } else {
            this.qtdEstoque -= demanda;
        }
    }

    public void elevarEstoque(int demanda) throws EstoqueElevadoException{
        if (this.qtdEstoque+demanda>this.estoqueMaximo){
            throw new EstoqueElevadoException();
        }else{
            qtdEstoque += demanda;
        }

    }
}
