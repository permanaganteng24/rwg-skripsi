describe("TC-18: Data Pelanggan (US-011)", () => {
  it("TC-18: sistem menampilkan data pelanggan yang tersimpan", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/users");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("Cypress Customer", { timeout: 15000 }).should("be.visible");
    cy.contains("customer@cypress.test", { timeout: 15000 }).should("be.visible");
  });
});