describe("TC-07: Keranjang Belanja - Ubah Jumlah Produk (US-005)", () => {
  it("TC-07: pengguna dapat mengubah jumlah produk sebelum menambahkan ke keranjang", () => {
    cy.loginAsCustomer();

    cy.visit("/products/cypress-test-chair");

    cy.window().should("have.property", "Livewire");
    cy.get('button[wire\\:click="incrementQty"]', { timeout: 15000 }).should("be.visible");

    cy.wait(1500);

    // Klik tombol "+" untuk menambah jumlah produk
    cy.get('button[wire\\:click="incrementQty"]').click();

    cy.wait(1500);

    // Jumlah produk harus bertambah dari 1 menjadi 2
    cy.contains("2", { timeout: 10000 }).should("exist");
  });
});