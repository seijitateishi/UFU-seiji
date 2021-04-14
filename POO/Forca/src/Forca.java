/*public class Forca {
    String word = "palavra";
    int tam = word.length();
    String[] guessedLetters = new String[tam];
    String[] usedLetters = new String[26];
    {
    for (int i = 0; i < this.word.length(); i++){
        this.guessedLetters[i] = "_";
    }
    public void addLetter (char letter){
        int index;
        for (int i = 0; i<tam ; i++) {//verificar se a letra digitada esta na string
            if (letter == this.word.charAt(i)) 
                this.guessedLetters[i] = letter;//se estiver a letra vai entrar na mesma posição no rightletters
            else
                index = usedLetters.indexOf(letter);
                if (index != (-1))
                    this.usedLetters[i] = letter;//se nao estiver a letra vai entrar na mesma posição no wrongletters  
        }
    }
    public void print (){
        System.out.println("letras usadas:"+usedLetters);
        System.out.println("\npalavra:"+this.guessedletters);
    }
    public Boolean end (){
        if (this.guessedLetters == this.word)
            return true; 
        else   
            return false;
        
    }
    }
}*/