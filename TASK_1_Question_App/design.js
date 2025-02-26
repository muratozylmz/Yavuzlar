document.getElementById("arama").addEventListener("input", aramaYap);

function aramaYap() {
    const aramaMetni =document.getElementById("arama").value.toLowerCase();
    const filtreliSorular = sorular.filter((soru) =>
        soru.text.toLowerCase().includes(aramaMetni) ||
    soru.cevaplar.cevap1.toLowerCase().includes(aramaMetni) ||
    soru.cevaplar.cevap2.toLowerCase().includes(aramaMetni) ||
    soru.cevaplar.cevap3.toLowerCase().includes(aramaMetni) ||
    soru.cevaplar.cevap4.toLowerCase().includes(aramaMetni) ||
    soru.dogruCevap.toLowerCase().includes(aramaMetni) ||
    soru.zorluk.toLowerCase().includes(aramaMetni)
    );
    sorulariListele(filtreliSorular);

}

function sorulariListele(filtreliSorular = sorular) {
    liste.innerHTML = "";
    filtreliSorular.forEach((soru,index) =>{
        const li = document.createElement("li");
        li.innerHTML = `
            <h3>soru: ${soru.text}</h3>
            <p>Cevap 1: ${soru.cevaplar.cevap1}</p>
            <p>Cevap 2: ${soru.cevaplar.cevap2}</p>
            <p>Cevap 3: ${soru.cevaplar.cevap3}</p>
            <p>Cevap 4: ${soru.cevaplar.cevap4}</p>
            <p> Dogru Cevap: ${soru.dogruCevap}</p>
            <p>Zorluk: ${soru.zorluk}</p>
            <button onclick="soruDuzenle(${index})">Düzenle</button>
            <button onclick="soruSil(${index})">Sil</button>
    `;
    liste.appendChild(li);
    });
}

function soruDuzenle(index) {
    const soru = sorular[index];
    document.getElementById("soru").value = soru.text;
    document.getElementById("cevap1").value = soru.cevaplar.cevap1;
    document.getElementById("cevap2").value = soru.cevaplar.cevap2;
    document.getElementById("cevap3").value = soru.cevaplar.cevap3;
    document.getElementById("cevap4").value = soru.cevaplar.cevap4;
    document.getElementById("dogruCevap").value = soru.dogruCevap;
    document.getElementById("zorluk").value = soru.zorluk;
    sorular.splice(index, 1); //Güncelleme işlemini bu string koduyla yapıyoruz.
    localStorage.setItem("sorular", JSON.stringify(sorular));
    sorulariListele();
}

function soruSil(index) {
    if(confirm("Seçilen soruyu silmek istediğinize emin misiniz?")) {
    sorular.splice(index, 1);
    localStorage.setItem("sorular", JSON.stringify(sorular));
    sorulariListele();
  }

}

if(localStorage.setItem("sorular")) {
    sorular = JSON.parse(localStorage.getItem("sorular"));
    sorulariListele();
}
