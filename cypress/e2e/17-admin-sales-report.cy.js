describe("TC-21: Laporan Penjualan (US-013)", () => {
  it("TC-21: sistem menampilkan data laporan penjualan", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin");
    cy.window().should("have.property", "Livewire");
    cy.wait(2500);

    cy.contains("Total Pemasukan", { timeout: 15000 }).should("be.visible");
    cy.contains("Pesanan Baru", { timeout: 15000 }).should("be.visible");
  });
});