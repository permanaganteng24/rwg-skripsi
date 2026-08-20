describe("TC-14: Manajemen Kategori - Tambah Kategori (US-009)", () => {
  it("TC-14: admin dapat menambahkan kategori baru", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/categories/create");
    cy.window().should("have.property", "Livewire");
    cy.get("#data\\.name", { timeout: 15000 }).should("be.visible");
    cy.wait(1500);

    const categoryName = "Cypress Kategori " + Date.now();

    cy.get("#data\\.name").type(categoryName);
    cy.get("#data\\.name").blur();
    cy.wait(1500);

    cy.contains("button", "Create").click({ force: true });
    cy.wait(3000);

    cy.url({ timeout: 15000 }).should("not.include", "/create");

    // Verifikasi kategori baru muncul di daftar
    cy.visit("/admin/categories");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);
    cy.contains(categoryName, { timeout: 15000 }).should("be.visible");
  });
});