describe("TC-01 & TC-02: Register dan Login (US-001)", () => {
  it("TC-01: pengguna dapat melakukan registrasi akun baru", () => {
    const randomEmail = `cypress${Date.now()}@example.com`;

    cy.visit("/register");

    cy.window().should("have.property", "Livewire");
    cy.get('input[wire\\:model="name"]').should("be.visible");

    cy.get('input[wire\\:model="name"]').type("Cypress Register User");
    cy.get('input[wire\\:model="email"]').type(randomEmail);
    cy.get('input[wire\\:model="password"]').type("password123");

    cy.contains("button", "Sign up").click();

    // Beri waktu lebih lama karena alur redirect register melewati
    // /checkout terlebih dahulu sebelum akhirnya ke /products (2 tahap).
    cy.url({ timeout: 15000 }).should("not.include", "/register");
    cy.url().should("not.include", "/login");
  });

  it("TC-02: pengguna dapat login dengan akun yang telah terdaftar", () => {
    cy.visit("/login");

    cy.window().should("have.property", "Livewire");
    cy.get('input[wire\\:model="email"]').should("be.visible");

    cy.get('input[wire\\:model="email"]').type("customer@cypress.test");
    cy.get('input[wire\\:model="password"]').type("password123");

    cy.contains("button", "Sign in").click();

    cy.url({ timeout: 15000 }).should("not.include", "/login");
  });
});