Cypress.Commands.add("loginAsCustomer", () => {
  cy.visit("/login");
  cy.window().should("have.property", "Livewire");
  cy.get('input[wire\\:model="email"]', { timeout: 15000 }).should("be.visible");
  cy.wait(1000);
  cy.get('input[wire\\:model="email"]').type("customer@cypress.test");
  cy.get('input[wire\\:model="password"]').type("password123");
  cy.contains("button", "Sign in").click();
});

Cypress.Commands.add("loginAsAdmin", () => {
  cy.visit("/admin/login");
  cy.window().should("have.property", "Livewire");
  cy.get('input[id="data.email"]', { timeout: 15000 }).should("be.visible");
  cy.wait(1000);
  cy.get('input[id="data.email"]').type("admin@cypress.test");
  cy.get('input[id="data.password"]').type("password123");
  cy.contains("button", "Sign in").click();
});