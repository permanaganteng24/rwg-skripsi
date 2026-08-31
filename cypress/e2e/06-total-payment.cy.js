describe("TC-08: Perhitungan Total Pembayaran (US-005)", () => {
  it("TC-08: sistem menghitung total pembayaran berdasarkan harga dan jumlah produk", () => {
    cy.loginAsCustomer();

    // Kosongkan cart dulu supaya tidak tercampur sisa item dari test sebelumnya
    cy.visit("/cart");
    cy.wait(1500);
    cy.get("body").then(($body) => {
      if ($body.text().includes("Remove")) {
        cy.contains("Remove").click({ force: true });
        cy.wait(2000);
      }
    });

    // Baru pasang intercept SETELAH proses bersih-bersih cart selesai,
    // supaya tidak ikut menangkap request dari aksi "Remove" di atas.
    cy.intercept("POST", "/livewire/update").as("livewireUpdate");

    cy.visit("/products/cypress-test-chair");
    cy.window().should("have.property", "Livewire");
    cy.get('button[wire\\:click="incrementQty"]', { timeout: 15000 }).should("be.visible");
    cy.wait(2000);

    cy.get('button[wire\\:click="incrementQty"]').click();
    cy.wait("@livewireUpdate");
    cy.contains("2", { timeout: 10000 }).should("exist");

    cy.get('button[wire\\:click="incrementQty"]').click();
    cy.wait("@livewireUpdate");
    cy.contains("3", { timeout: 10000 }).should("exist");

    cy.get('button[wire\\:click="addToCart"]').click();
    cy.wait("@livewireUpdate");
    cy.wait(2000);

    cy.visit("/cart");
    cy.wait(2000);

    cy.get("body", { timeout: 15000 }).should(($body) => {
      const text = $body.text().replace(/\s/g, "");
      expect(text).to.match(/1[.,]500[.,]000/);
    });
  });
});