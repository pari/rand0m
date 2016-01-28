class CreatePaychecks < ActiveRecord::Migration
  def self.up
    create_table :paychecks do |t|
      t.date :payperiod_startdate
      t.date :payperiod_enddate
      t.date :paydate
      t.float :payamount
      t.references :employee

      t.timestamps
    end
  end

  def self.down
    drop_table :paychecks
  end
end
