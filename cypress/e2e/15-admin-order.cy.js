describe("TC-16: Manajemen Pesanan - Lihat Data Pesanan (US-010)", () => {
  it("TC-16: sistem menampilkan data pesanan pelanggan", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/orders");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("ORD-CYPRESS-TEST", { timeout: 15000 }).should("be.visible");
  });
});

describe("TC-17: Manajemen Pesanan - Ubah Status Pesanan (US-010)", () => {
  it("TC-17: admin dapat mengubah status pesanan", () => {
    cy.loginAsAdmin();
    cy.url({ timeout: 15000 }).should("not.include", "/admin/login");
    cy.wait(2000);

    cy.visit("/admin/orders");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.contains("tr", "ORD-CYPRESS-TEST", { timeout: 15000 })
      .contains("Edit")
      .click();

    cy.wait(2500);

    cy.get("#data\\.order_status", { timeout: 15000 }).should("be.visible");
    cy.wait(1500);

    cy.get("#data\\.order_status").select("processing");
    cy.wait(1000);

    cy.contains("button", "Save changes").click({ force: true });
    cy.wait(3000);

    // Verifikasi perubahan tersimpan lewat daftar pesanan
    cy.visit("/admin/orders");
    cy.window().should("have.property", "Livewire");
    cy.wait(2000);

    cy.get("table", { timeout: 15000 }).should(($table) => {
      const text = $table.text();
      expect(text).to.include("ORD-CYPRESS-TEST");
      expect(text.toLowerCase()).to.include("processing");
    });
  });
});