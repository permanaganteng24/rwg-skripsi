describe("TC-05: Detail Produk (US-004)", () => {
  it("TC-05: sistem menampilkan informasi detail produk", () => {
    cy.visit("/products");

    // Memilih salah satu produk dari katalog
    cy.contains("Cypress Test Chair").click();

    // Setelah diklik, pengguna diarahkan ke halaman detail produk
    cy.url().should("include", "/products/cypress-test-chair");

    // Menggunakan cy.get("body") agar tahan terhadap DOM yang berganti
    // akibat proses re-render Livewire, sehingga tidak error "detached from DOM".
    cy.get("body", { timeout: 10000 }).should(($body) => {
      expect($body.text()).to.include("Cypress Test Chair");
    });

    cy.get("body", { timeout: 10000 }).should(($body) => {
      expect($body.text().replace(/\s/g, "")).to.match(/500[.,]000/);
    });
  });
});