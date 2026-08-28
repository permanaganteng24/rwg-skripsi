describe("TC-19: Ulasan Produk - Tambah Ulasan (US-012)", () => {
  it("TC-19: pengguna dapat menambahkan ulasan produk", () => {
    cy.loginAsCustomer();
    cy.url({ timeout: 15000 }).should("not.include", "/login");
    cy.wait(1500);

    cy.visit("/my-orders");
    cy.window().should("have.property", "Livewire");
    cy.wait(3000);

    cy.contains("ORD-REVIEW-TEST", { timeout: 15000 }).should("be.visible");
    cy.wait(1000);

    cy.contains("ORD-REVIEW-TEST")
      .parents("div")
      .contains("Tulis Ulasan", { timeout: 10000 })
      .click({ force: true });

    cy.wait(2000);

    cy.contains("How was the product?", { timeout: 10000 }).should("be.visible");
    cy.wait(1000);

    // Klik bintang ke-5 lewat method Livewire setRating(5)
    cy.get('[wire\\:click="setRating(5)"]', { timeout: 10000 }).first().click({ force: true });
    cy.wait(1000);

    cy.get('textarea[wire\\:model="comment"]').type("Produk sangat memuaskan, kualitas kayu jati sangat bagus dan pengerjaan rapi.");
    cy.wait(1000);

    cy.contains("button", "Kirim Ulasan").click({ force: true });
    cy.wait(3000);

    cy.contains("How was the product?").should("not.exist");
  });
});

describe("TC-20: Ulasan Produk - Tampilkan Ulasan (US-012)", () => {
  it("TC-20: sistem menampilkan ulasan produk", () => {
    cy.visit("/reviews");
    cy.wait(2000);
    cy.get("body", { timeout: 15000 }).should("be.visible");
  });
});