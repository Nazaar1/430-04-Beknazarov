using System;
using System.Collections.Generic;
using System.Data;
using System.IO;
using System.Linq;
using System.Windows.Forms;

namespace project6
{
    public partial class Form1 : Form
    {
        private List<Teacher> teachers = new List<Teacher>();
        private bool isDataChanged = false;

        public Form1()
        {
            InitializeComponent();
            InitializeListView();
        }

        private void textBox4_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (!char.IsControl(e.KeyChar) && !(e.KeyChar >= '0' && e.KeyChar <= '9') || textBox4.TextLength >= 3)
            {
                e.Handled = true;
            }
        }
        private void InitializeListView()
        {
            listView1.Columns.Add("Фамилия");
            listView1.Columns.Add("Имя");
            listView1.Columns.Add("Отчество");
            listView1.Columns.Add("Код дисциплины");
            listView1.Columns.Add("Название дисциплины");
            listView1.Columns[0].Width = 140;
            listView1.Columns[1].Width = 100;
            listView1.Columns[2].Width = 140;
            listView1.Columns[3].Width = 120;
            listView1.Columns[4].Width = 140;
        }
        private void button1_Click(object sender, EventArgs e)
        {
            if (ValidateFields())
            {
                Teacher teacher = new Teacher
                {
                    LastName = textBox1.Text,
                    FirstName = textBox2.Text,
                    Patronymic = textBox3.Text,
                    Discip_Code = textBox4.Text,
                    Discip_Name = textBox5.Text
                };
                teachers.Add(teacher);
                UpdateListView();
                isDataChanged = true;
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (listView1.SelectedItems.Count > 0 && ValidateFields())
            {
                var selectedItem = listView1.SelectedItems[0];
                var teacher = teachers[selectedItem.Index];

                teacher.LastName = textBox1.Text;
                teacher.FirstName = textBox2.Text;
                teacher.Patronymic = textBox3.Text;
                teacher.Discip_Code = textBox4.Text;
                teacher.Discip_Name = textBox5.Text;

                UpdateListView();
                isDataChanged = true;
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (listView1.SelectedItems.Count > 0)
            {
                teachers.RemoveAt(listView1.SelectedItems[0].Index);
                UpdateListView();
                isDataChanged = true;
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            string projectFolder = AppDomain.CurrentDomain.BaseDirectory;
            string filePath = Path.Combine(projectFolder, "teachers.txt");

            using (StreamWriter writer = new StreamWriter(filePath))
            {
                foreach (var teacher in teachers)
                {
                    writer.WriteLine(teacher.ToString());
                }
            }
        }

        private void button5_Click(object sender, EventArgs e)
        {
            using (StreamReader reader = new StreamReader("teachers.txt"))
            {
                teachers.Clear();
                while (!reader.EndOfStream)
                {
                    var line = reader.ReadLine();
                    var parts = line.Split(';');
                    teachers.Add(new Teacher
                    {
                        LastName = parts[0],
                        FirstName = parts[1],
                        Patronymic = parts[2],
                        Discip_Code = parts[3],
                        Discip_Name = parts[4]
                    });
                }
            }
            UpdateListView();
        }

        private void SortByLastName()
        {
            teachers = teachers.OrderBy(t => t.LastName).ToList();
            UpdateListView();
        }

        private void SortByDisciplineCode()
        {
            teachers = teachers.OrderBy(t => t.Discip_Code).ToList();
            UpdateListView();
        }
        private bool ValidateFields()
        {
            if (string.IsNullOrWhiteSpace(textBox1.Text) || !char.IsUpper(textBox1.Text[0]))
            {
                MessageBox.Show("Фамилия должна начинаться с заглавной буквы и не быть пустой.");
                return false;
            }

            if (string.IsNullOrWhiteSpace(textBox2.Text) || !char.IsUpper(textBox2.Text[0]))
            {
                MessageBox.Show("Имя должно начинаться с заглавной буквы и не быть пустым.");
                return false;
            }
            if (string.IsNullOrWhiteSpace(textBox3.Text) || !char.IsUpper(textBox3.Text[0]))
            {
                MessageBox.Show("Отчество должно начинаться с заглавной буквы и не быть пустым.");
                return false;
            }
            if (string.IsNullOrWhiteSpace(textBox5.Text) || !char.IsUpper(textBox5.Text[0]))
            {
                MessageBox.Show("Название Дисциплины должно начинаться с заглавной буквы и не быть пустым.");
                return false;
            }
            return true;
        }
        private void UpdateListView()
        {
            listView1.Items.Clear();

            foreach (var teacher in teachers)
            {
                var item = new ListViewItem(teacher.LastName);
                item.SubItems.Add(teacher.FirstName);
                item.SubItems.Add(teacher.Patronymic);
                item.SubItems.Add(teacher.Discip_Code);
                item.SubItems.Add(teacher.Discip_Name);

                listView1.Items.Add(item);
            }
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (isDataChanged)
            {
                var result = MessageBox.Show("Сохранить изменения?", "Подтверждение", MessageBoxButtons.YesNoCancel);

                if (result == DialogResult.Yes)
                {
                    button4.PerformClick();
                }
                else if (result == DialogResult.Cancel)
                {
                    e.Cancel = true;
                }
            }
        }

        
    }
}

