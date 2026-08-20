describe("TC-15: Manajemen Kategori - Hapus Kategori (US-009)", () => {
  it("TC-15: admin dapat menghapus kategori", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/categories");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("Kategori Untuk Dihapus", { timeout: 15000 }).should("be.visible");

    cy.contains("tr", "Kategori Untuk Dihapus")
      .contains("Delete")
      .click();

    cy.wait(2000);

    cy.contains("Delete", { timeout: 10000 }).should("be.visible");
    cy.wait(1000);

    cy.get('[role="dialog"], .fi-modal').contains("button", "Delete").click({ force: true });
    cy.wait(3000);

    cy.reload();
    cy.wait(2000);
    cy.contains("Kategori Untuk Dihapus").should("not.exist");
  });
});