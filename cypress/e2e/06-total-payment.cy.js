describe("TC-08: Perhitungan Total Pembayaran (US-005)", () => {
  it("TC-08: sistem menghitung total pembayaran berdasarkan harga dan jumlah produk", () => {
    cy.loginAsCustomer();

    cy.visit("/products/cypress-test-chair");

    cy.window().should("have.property", "Livewire");
    cy.get('button[wire\\:click="incrementQty"]', { timeout: 15000 }).should("be.visible");
    cy.wait(2000);

    cy.get('button[wire\\:click="incrementQty"]').click();
    cy.wait(1500);
    cy.get('button[wire\\:click="incrementQty"]').click();
    cy.wait(1500);

    cy.contains("3", { timeout: 10000 }).should("exist");
    cy.wait(1000);

    cy.get('button[wire\\:click="addToCart"]').click();
    cy.wait(3000);

    cy.visit("/cart");
    cy.wait(2000);

    cy.get("body", { timeout: 15000 }).should(($body) => {
      const text = $body.text().replace(/\s/g, "");
      expect(text).to.match(/1[.,]500[.,]000/);
    });
  });
});