describe("TC-03: Informasi Toko (US-002)", () => {
  it("TC-03: sistem menampilkan informasi toko", () => {
    cy.visit("/about");

    cy.get("body").should("be.visible");
    cy.contains("Rizqi Wood Gallery", { matchCase: false }).should("exist");
  });
});

describe("TC-04: Katalog Produk (US-003)", () => {
  it("TC-04: sistem menampilkan daftar produk yang tersedia", () => {
    cy.visit("/products");

    cy.get("body").should("be.visible");

    // Produk testing yang sudah kita siapkan sebelumnya harus muncul di katalog.
    cy.contains("Cypress Test Chair").should("be.visible");
  });
});