import { useState } from "react";
export default function Inserisci(){
    const [inserisci, setInserisci] = useState(true);
    const [nome, setNome] = useState("");
    const [cognome, setCognome] = useState("");

    function impostaNome(evento){   
        setNome(evento.target.value)
    }
    function impostaCognome(evento){   
        setCognome(evento.target.value)
    }
    async function inserisciAlunno(){
    await fetch("http://localhost:8080/alunni", {
        method:"POST",
        headers: {"Content-Type": "application.json"},
        body: JSON.stringify({nome: nome, cognome:cognome})})
    }
    return(
        <>
        {!inserisci ? (
            <button onClick={() => {setInserisci(true)}}>aggiungi alunno</button>
          ):(
            <>
            <input type="text" placeholder='nome' onChange={(e) => {impostaNome}}/>
            <input type="text" placeholder='cognome'onChange={(e) => {impostaCognome}}/>
            <button onClick={inserisciAlunno}>inserisci</button>
            <button onClick={() => setInserisci(false)}>annulla</button>
            </>
          )
        }
        </>
    )
}