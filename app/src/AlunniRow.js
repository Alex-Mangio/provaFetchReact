import { useState } from "react";
export default function AlunniRow({a, caricaAlunni}){

    const [inConferma, setInConferma] = useState(false);
    async function cancellaAlunno(){
        await fetch(`http://localhost:8080/alunni/${a.id}`,{method:'DELETE'});
        caricaAlunni();
    }
    return(
        <tr>
            <td>{a.id}</td>
            <td>{a.nome}</td>
            <td>{a.cognome}</td>
            <td>
            {!inConferma ? (
                <button onClick={() => {setInConferma(true)}}>Delete</button>
            ):(
                <div>
                    sei sicuro?
                    <button onClick={cancellaAlunno}>Si</button>
                    <button onClick={() => {setInConferma(false)}}>No</button>
                </div>
            )}
            </td>
        </tr>
    );
}