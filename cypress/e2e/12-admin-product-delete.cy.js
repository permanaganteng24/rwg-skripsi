describe("TC-13: Manajemen Produk - Hapus Produk (US-008)", () => {
  it("TC-13: admin dapat menghapus produk", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/products");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("Produk Untuk Dihapus", { timeout: 15000 }).should("be.visible");

    cy.contains("tr", "Produk Untuk Dihapus")
      .contains("Delete")
      .click();

    cy.wait(2000);

    // Pastikan dialog konfirmasi "Delete product" benar-benar muncul dulu
    cy.contains("Delete product", { timeout: 10000 }).should("be.visible");
    cy.wait(1000);

    // Klik tombol "Delete" berwarna merah (bg-danger) di dalam dialog konfirmasi
    cy.get('[role="dialog"], .fi-modal').contains("button", "Delete").click({ force: true });
    cy.wait(3000);

    cy.reload();
    cy.wait(2000);
    cy.contains("Produk Untuk Dihapus").should("not.exist");
  });
});