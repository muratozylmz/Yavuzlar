const Form = document.getElementById("Form");
const liste = document.getElementById("liste");

let sorular = [];

Form.addEventListener("submit", (e) => {
    e.preventDefault();
    const soru = document.getElementById("soru").value;
    const cevap1 = document.getElementById("cevap1").value;
    const cevap2 = document.getElementById("cevap2").value;
    const cevap3 = document.getElementById("cevap3").value;
    const cevap4 = document.getElementById("cevap4").value;
    const dogruCevap = document.getElementById("dogruCevap").value;
    const zorluk = document.getElementById("zorluk").value;

    const soru1 = {

        text: soru,
        cevaplar: {
            cevap1: cevap1,
            cevap2: cevap2,
            cevap3: cevap3,
            cevap4: cevap4
        },
        dogruCevap: dogruCevap,
        zorluk: zorluk,
    };
    sorular.push(soru1);
    Form.reset();
    localStorage.setItem("sorular", JSON.stringify(sorular));
    sorulariListele();
    });

    function sorulariListele() {
        liste.innerHTML = "";
        sorular.forEach((soru, index) => {
            const li = document.createElement("li");
            li.innerHTML = `
            <h3>Soru: ${soru.text}</h3>
            <p>Cevap 1: ${soru.cevaplar.cevap1}</p>
            <p>Cevap 2: ${soru.cevaplar.cevap2}</p>
            <p>Cevap 3: ${soru.cevaplar.cevap3}</p>
            <p>Cevap 4: ${soru.cevaplar.cevap4}</p>
            <p> Dogru Cevap: ${soru.dogruCevap}</p>
            <p>Zorluk: ${soru.zorluk}</p>
            `;
            liste.appendChild(li);
        });
    };

    if (localStorage.getItem("sorular")) {
        sorular = JSON.parse(localStorage.getItem("sorular"));
        sorulariGoster();
    }
   
