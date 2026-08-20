describe("TC-12: Manajemen Produk - Ubah Data Produk (US-008)", () => {
  it("TC-12: admin dapat mengubah data produk", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/products");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("tr", "Cypress Test Chair", { timeout: 15000 })
      .contains("Edit")
      .click();

    cy.wait(2500);

    cy.url({ timeout: 15000 }).should("include", "/edit");

    cy.get("#data\\.price", { timeout: 15000 }).should("be.visible");
    cy.wait(1500);

    cy.get("#data\\.price").clear();
    cy.wait(500);
    cy.get("#data\\.price").type("650000");
    cy.wait(500);
    cy.get("#data\\.price").blur();
    cy.wait(1500);

    cy.get("#data\\.price").should("have.value", "650000");

    cy.contains("button", "Save changes").click({ force: true });
    cy.wait(4000);

    cy.visit("/admin/products");
    cy.wait(1500);

    cy.url({ timeout: 10000 }).then((url) => {
      if (url.includes("/admin/login")) {
        cy.get('input[id="data.email"]').type("admin@cypress.test");
        cy.get('input[id="data.password"]').type("password123");
        cy.contains("button", "Sign in").click();
        cy.wait(2000);
        cy.visit("/admin/products");
      }
    });

    cy.window().should("have.property", "Livewire");
    cy.wait(3000);

    cy.get("table", { timeout: 15000 }).should(($table) => {
      const text = $table.text().replace(/\s/g, "");
      expect(text).to.include("CypressTestChair");
      expect(text).to.match(/650[.,]000/);
    });
  });
});