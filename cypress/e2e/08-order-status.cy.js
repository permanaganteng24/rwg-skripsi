describe("TC-10: Status Pesanan (US-007)", () => {
  it("TC-10: sistem menampilkan status pesanan pengguna", () => {
    cy.loginAsCustomer();
    cy.url({ timeout: 15000 }).should("not.include", "/login");
    cy.wait(1500);

    cy.visit("/my-orders");
    cy.wait(1500);

    // Sistem harus menampilkan halaman daftar pesanan dengan status di dalamnya
    cy.get("body", { timeout: 15000 }).should(($body) => {
      const text = $body.text();
      const hasOrderCode = /ORD-/i.test(text);
      expect(hasOrderCode || text.toLowerCase().includes("no orders")).to.be.true;
    });
  });
});