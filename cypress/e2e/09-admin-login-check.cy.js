describe("Verifikasi Login Admin", () => {
  it("admin dapat login ke panel admin", () => {
    cy.loginAsAdmin();
    cy.wait(2000);
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
  });
});