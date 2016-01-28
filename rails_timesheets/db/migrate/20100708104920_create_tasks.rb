class CreateTasks < ActiveRecord::Migration
  def self.up
    create_table :tasks do |t|
      t.string :name
      t.text :notes
      t.integer :minutes
      t.integer :project_id
      t.integer :user_id
      t.date :tday

      t.timestamps
    end
  end

  def self.down
    drop_table :tasks
  end
end
