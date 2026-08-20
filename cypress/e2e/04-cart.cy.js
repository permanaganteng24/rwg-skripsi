describe("TC-06: Keranjang Belanja - Tambah Produk (US-005)", () => {
  it("TC-06: pengguna dapat menambahkan produk ke keranjang", () => {
    cy.loginAsCustomer();

    cy.visit("/products/cypress-test-chair");

    cy.window().should("have.property", "Livewire");
    cy.get('button[wire\\:click="addToCart"]', { timeout: 15000 }).should("be.visible");

    cy.wait(2000);

    cy.get('button[wire\\:click="addToCart"]').click();

    cy.wait(2000);

    cy.visit("/cart");

    cy.get("body", { timeout: 15000 }).should(($body) => {
      expect($body.text()).to.include("Cypress Test Chair");
    });
  });
});